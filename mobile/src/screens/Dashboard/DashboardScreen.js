/**
 * Dashboard Screen
 * Main dashboard with stats and charts
 */

import React, {useState, useEffect} from 'react';
import {
  View,
  Text,
  ScrollView,
  StyleSheet,
  RefreshControl,
  TouchableOpacity,
  Dimensions,
} from 'react-native';
import LinearGradient from 'react-native-linear-gradient';
import Icon from 'react-native-vector-icons/Ionicons';
import {LineChart} from 'react-native-chart-kit';
import {useAuth} from '../../context/AuthContext';
import {dashboardApi} from '../../services/api';
import {colors, typography, spacing, borderRadius, shadows} from '../../utils/theme';

const screenWidth = Dimensions.get('window').width;

const DashboardScreen = () => {
  const {user} = useAuth();
  const [refreshing, setRefreshing] = useState(false);
  const [stats, setStats] = useState({
    totalCustomers: 1248,
    vipCustomers: 84,
    atRiskCustomers: 12,
    emailsSent: 3567,
  });

  const [chartData, setChartData] = useState({
    labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
    datasets: [{data: [45, 52, 48, 67, 73, 86, 92]}],
  });

  useEffect(() => {
    loadDashboardData();
  }, []);

  const loadDashboardData = async () => {
    try {
      // const response = await dashboardApi.getStats();
      // setStats(response.stats);
      // setChartData(response.chartData);
    } catch (error) {
      console.error('Error loading dashboard:', error);
    }
  };

  const onRefresh = async () => {
    setRefreshing(true);
    await loadDashboardData();
    setRefreshing(false);
  };

  return (
    <View style={styles.container}>
      {/* Header */}
      <LinearGradient
        colors={colors.gradient.primary}
        style={styles.header}
        start={{x: 0, y: 0}}
        end={{x: 1, y: 1}}>
        <View style={styles.headerContent}>
          <View>
            <Text style={styles.greeting}>Bonjour,</Text>
            <Text style={styles.userName}>{user?.name || 'John'} 👋</Text>
          </View>
          <TouchableOpacity style={styles.notificationButton}>
            <Icon name="notifications-outline" size={24} color={colors.white} />
            <View style={styles.notificationBadge}>
              <Text style={styles.notificationBadgeText}>3</Text>
            </View>
          </TouchableOpacity>
        </View>
      </LinearGradient>

      <ScrollView
        style={styles.content}
        showsVerticalScrollIndicator={false}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
        }>
        {/* Stats Cards */}
        <View style={styles.statsContainer}>
          <View style={styles.statsRow}>
            <StatCard
              icon="people"
              label="Total Clients"
              value={stats.totalCustomers}
              change="+12.5%"
              positive={true}
              gradient={colors.gradient.primary}
            />
            <StatCard
              icon="star"
              label="Clients VIP"
              value={stats.vipCustomers}
              change="+8.2%"
              positive={true}
              gradient={colors.gradient.warning}
            />
          </View>
          <View style={styles.statsRow}>
            <StatCard
              icon="warning"
              label="À Risque"
              value={stats.atRiskCustomers}
              change="-3.1%"
              positive={false}
              gradient={colors.gradient.danger}
            />
            <StatCard
              icon="mail"
              label="Emails"
              value={stats.emailsSent}
              change="+24.7%"
              positive={true}
              gradient={colors.gradient.success}
            />
          </View>
        </View>

        {/* Chart Card */}
        <View style={styles.chartCard}>
          <Text style={styles.chartTitle}>Évolution des Visites</Text>
          <LineChart
            data={chartData}
            width={screenWidth - 64}
            height={220}
            chartConfig={{
              backgroundColor: colors.white,
              backgroundGradientFrom: colors.white,
              backgroundGradientTo: colors.white,
              decimalPlaces: 0,
              color: (opacity = 1) => `rgba(99, 102, 241, ${opacity})`,
              labelColor: (opacity = 1) => `rgba(100, 116, 139, ${opacity})`,
              style: {
                borderRadius: 16,
              },
              propsForDots: {
                r: '6',
                strokeWidth: '2',
                stroke: colors.primary,
              },
            }}
            bezier
            style={styles.chart}
          />
        </View>

        {/* Quick Actions */}
        <Text style={styles.sectionTitle}>Actions Rapides</Text>
        <View style={styles.actionsContainer}>
          <QuickActionCard
            icon="sparkles"
            title="Générer du Contenu IA"
            subtitle="Posts pour réseaux sociaux"
            color={colors.primary}
          />
          <QuickActionCard
            icon="mail"
            title="Nouvelle Campagne"
            subtitle="Créer et envoyer"
            color={colors.success}
          />
          <QuickActionCard
            icon="people"
            title="Gérer les Segments"
            subtitle="Organiser vos clients"
            color={colors.warning}
          />
          <QuickActionCard
            icon="star"
            title="Répondre aux Avis"
            subtitle="12 avis en attente"
            color={colors.danger}
          />
        </View>

        {/* Recent Activity */}
        <Text style={styles.sectionTitle}>Activité Récente</Text>
        <View style={styles.activityContainer}>
          <ActivityItem
            icon="star"
            title="Nouveau Client VIP"
            subtitle="Marie Martin a été promue VIP"
            time="Il y a 5 min"
            iconColor={colors.warning}
          />
          <ActivityItem
            icon="mail"
            title="Campagne Envoyée"
            subtitle="Newsletter Automne - 248 destinataires"
            time="Il y a 1h"
            iconColor={colors.success}
          />
          <ActivityItem
            icon="warning"
            title="Client à Risque"
            subtitle="Jean Dupont - Pas de visite depuis 65j"
            time="Il y a 3h"
            iconColor={colors.danger}
          />
        </View>
      </ScrollView>
    </View>
  );
};

// Stat Card Component
const StatCard = ({icon, label, value, change, positive, gradient}) => (
  <View style={styles.statCard}>
    <LinearGradient
      colors={gradient}
      style={styles.statIconContainer}
      start={{x: 0, y: 0}}
      end={{x: 1, y: 1}}>
      <Icon name={icon} size={24} color={colors.white} />
    </LinearGradient>
    <Text style={styles.statLabel}>{label}</Text>
    <Text style={styles.statValue}>{value.toLocaleString()}</Text>
    <Text style={[styles.statChange, {color: positive ? colors.success : colors.danger}]}>
      <Icon name={positive ? 'arrow-up' : 'arrow-down'} size={12} /> {change}
    </Text>
  </View>
);

// Quick Action Card Component
const QuickActionCard = ({icon, title, subtitle, color}) => (
  <TouchableOpacity style={styles.actionCard}>
    <View style={[styles.actionIcon, {backgroundColor: `${color}15`}]}>
      <Icon name={icon} size={24} color={color} />
    </View>
    <Text style={styles.actionTitle}>{title}</Text>
    <Text style={styles.actionSubtitle}>{subtitle}</Text>
  </TouchableOpacity>
);

// Activity Item Component
const ActivityItem = ({icon, title, subtitle, time, iconColor}) => (
  <View style={styles.activityItem}>
    <View style={[styles.activityIcon, {backgroundColor: `${iconColor}15`}]}>
      <Icon name={icon} size={20} color={iconColor} />
    </View>
    <View style={styles.activityContent}>
      <Text style={styles.activityTitle}>{title}</Text>
      <Text style={styles.activitySubtitle}>{subtitle}</Text>
    </View>
    <Text style={styles.activityTime}>{time}</Text>
  </View>
);

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.background,
  },
  header: {
    paddingTop: 60,
    paddingBottom: 30,
    paddingHorizontal: spacing.xl,
    borderBottomLeftRadius: 30,
    borderBottomRightRadius: 30,
  },
  headerContent: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  greeting: {
    fontSize: typography.fontSize.base,
    color: colors.white,
    opacity: 0.9,
  },
  userName: {
    fontSize: typography.fontSize['2xl'],
    fontWeight: 'bold',
    color: colors.white,
    marginTop: 4,
  },
  notificationButton: {
    position: 'relative',
  },
  notificationBadge: {
    position: 'absolute',
    top: -4,
    right: -4,
    backgroundColor: colors.danger,
    borderRadius: 10,
    width: 20,
    height: 20,
    justifyContent: 'center',
    alignItems: 'center',
  },
  notificationBadgeText: {
    color: colors.white,
    fontSize: 10,
    fontWeight: 'bold',
  },
  content: {
    flex: 1,
    paddingHorizontal: spacing.base,
    marginTop: -20,
  },
  statsContainer: {
    marginBottom: spacing.base,
  },
  statsRow: {
    flexDirection: 'row',
    gap: spacing.base,
    marginBottom: spacing.base,
  },
  statCard: {
    flex: 1,
    backgroundColor: colors.white,
    borderRadius: borderRadius.lg,
    padding: spacing.base,
    ...shadows.md,
  },
  statIconContainer: {
    width: 48,
    height: 48,
    borderRadius: borderRadius.md,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: spacing.sm,
  },
  statLabel: {
    fontSize: typography.fontSize.xs,
    color: colors.textLight,
    marginBottom: 4,
  },
  statValue: {
    fontSize: typography.fontSize['2xl'],
    fontWeight: 'bold',
    color: colors.text,
    marginBottom: 4,
  },
  statChange: {
    fontSize: typography.fontSize.xs,
    fontWeight: '600',
  },
  chartCard: {
    backgroundColor: colors.white,
    borderRadius: borderRadius.lg,
    padding: spacing.base,
    marginBottom: spacing.base,
    ...shadows.md,
  },
  chartTitle: {
    fontSize: typography.fontSize.lg,
    fontWeight: '600',
    color: colors.text,
    marginBottom: spacing.base,
  },
  chart: {
    marginVertical: 8,
    borderRadius: borderRadius.md,
  },
  sectionTitle: {
    fontSize: typography.fontSize.lg,
    fontWeight: 'bold',
    color: colors.text,
    marginVertical: spacing.base,
    marginLeft: 4,
  },
  actionsContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: spacing.base,
    marginBottom: spacing.base,
  },
  actionCard: {
    width: (screenWidth - spacing.base * 4) / 2,
    backgroundColor: colors.white,
    borderRadius: borderRadius.lg,
    padding: spacing.base,
    ...shadows.sm,
  },
  actionIcon: {
    width: 48,
    height: 48,
    borderRadius: borderRadius.md,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: spacing.sm,
  },
  actionTitle: {
    fontSize: typography.fontSize.sm,
    fontWeight: '600',
    color: colors.text,
    marginBottom: 4,
  },
  actionSubtitle: {
    fontSize: typography.fontSize.xs,
    color: colors.textLight,
  },
  activityContainer: {
    backgroundColor: colors.white,
    borderRadius: borderRadius.lg,
    padding: spacing.base,
    marginBottom: spacing.xl,
    ...shadows.md,
  },
  activityItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: spacing.sm,
    borderBottomWidth: 1,
    borderBottomColor: colors.borderLight,
  },
  activityIcon: {
    width: 40,
    height: 40,
    borderRadius: borderRadius.md,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: spacing.sm,
  },
  activityContent: {
    flex: 1,
  },
  activityTitle: {
    fontSize: typography.fontSize.sm,
    fontWeight: '600',
    color: colors.text,
    marginBottom: 2,
  },
  activitySubtitle: {
    fontSize: typography.fontSize.xs,
    color: colors.textLight,
  },
  activityTime: {
    fontSize: typography.fontSize.xs,
    color: colors.textMuted,
  },
});

export default DashboardScreen;
